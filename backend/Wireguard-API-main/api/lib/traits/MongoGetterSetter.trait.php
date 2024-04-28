<?php

/**
* Can be used with any mongodb collection associated class.
* The class should have $this->data where the document is reflected.
* The class/document should have been identified by _id as default.
*
*/
trait MongoGetterSetter
{
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) == "get") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 3));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            // print_r($this->data);
            if (isset($this->data->$property)) {
                // Sanitizing the return data if filter argument is passed
                if (isset($args[0])) {
                    if ($args[0] == "nofilter") {
                        return $this->data->$property;
                    }
                } else {
                    return $this->filter_data($this->data->$property);
                }
            } else {
                $bt = debug_backtrace();
                $caller = array_shift($bt);
                throw new Exception("Property ".__CLASS__."::data->$property does not exist on line ".$caller['line']." in file ".$caller['file'].".");
            }
        } if (substr($method, 0, 3) == "has") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 3));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            return isset($this->data->$property);
        } elseif (substr($method, 0, 3) == "set") {
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 3));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));

            //Access .set("auth.username", "value");
            if (strlen($property) == 0 and isset($args[0]) and !isset($args[1])) {
                $this->data = $this->collection->findOneAndUpdate(
                    [
                    '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
                ],
                    [
                    '$set' => $args[0]
                ],
                    [
                    'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
                ]
                );
            }
            elseif (strlen($property) == 0 and isset($args[1])) {
                $this->data = $this->collection->findOneAndUpdate(
                    [
                    '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
                ],
                    [
                    '$set' => [
                        $args[0] => $args[1]
                    ]
                ],
                    [
                    'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
                ]
                );
            } elseif (strlen($property) == 0 and !isset($args[1]) and is_array($args[0])) {
                //Pass whole array to be set
                $this->data = $this->collection->findOneAndUpdate(
                    [
                    '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
                ],
                    [
                    '$set' => $args[0]
                ],
                    [
                    'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
                ]
                );
            } else {
                $this->data = $this->collection->findOneAndUpdate(
                    [
                    '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
                ],
                    [
                    '$set' => [
                        $property => $args[0]
                    ]
                ],
                    [
                    'returnDocument' => MongoDB\Operation\FindOneAndUpdate::RETURN_DOCUMENT_AFTER
                ]
                );
            }
        } elseif (substr($method, 0, 2) == "is") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 2));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            if (property_exists($this->data, $property) and isset($this->data->$property)) {
                return boolval($this->data->$property);
            } else {
                return false;
            }
        } elseif (substr($method, 0, 5) == "unset") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 5));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            $this->collection->updateOne([
                '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
            ], [
                '$unset' => [
                    $property => ""
                ]
                ]);
        } elseif (substr($method, 0, 4) == "push") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 4));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            $this->collection->updateOne([
                '_id' => new MongoDB\BSON\ObjectId($this->getID(true))
            ], [
                '$push' => [
                    $property => $args[0]
                ]
            ]);
        } elseif (substr($method, 0, 2) == "in") {
            // Use like $this->inTags(["tag1", "tag2"]]) will return if this document has any of the tags
            if (!is_array($args[0])) {
                $args[0] = array($args[0]);
            }
            $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($method, 2));
            $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
            return $this->collection->findOne([
                '_id' => new MongoDB\BSON\ObjectId($this->getID(true)),
                $property => [
                    '$in' => $args[0]
                ]

            ]);
        } else {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            throw new Exception("Method ".__CLASS__."::$method does not exist on line ".$caller['line']." in file ".$caller['file'].".");
        }
    }

    //TODO: Change this _id into string and understand implications.
    public function getID($mongoid=true)
    {
        if ($mongoid) {
            return $this->data->_id;
        } else {
            return $this->data->id;
        }
    }

    public function delete()
    {
        return $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($this->id)])->isAcknowledged();
    }

    public function _update_data()
    {
        $this->data = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($this->getID(true))]);
    }

    public function filter_data($data)
    {
        if (is_object($data)) {
            return $data;
        }

        $filter = new HTMLPurifier();
        $purified_data = $filter->purify($data);

        return $purified_data;
    }
}