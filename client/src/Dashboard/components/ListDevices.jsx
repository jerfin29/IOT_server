import React,{useState,useEffect} from 'react'
import axios from 'axios';

function ListDevices() {

    const [devices, setDevices] = useState([]);
    const token = localStorage.getItem('token');

    useEffect(() => {
        axios.post('http://94.237.79.8/api/wg/get_peers',{},{
            headers:{
                'Authorization':token
            } 
        })
        .then( res => setDevices(res.data.peers))
    
    },[]
    )

  return (
    <main>
        <h1 className=' text-3xl font-bold montserrat flex items-center justify-center w-auto py-10 pb-20'>List of Devices </h1>

        <section className='grid grid-cols-3 justify-center items-center gap-10 montserrat'>
            { devices.map( (device,index) => (
                <div key={index} className='rounded-md bg-gay-200'>
                    <p>Peer : {device.peer}</p>
                    <p>Endpoint : {device.endpoint}</p>
                    <p>Allowed Ip : {device['allowed ips']}</p>  
                    <p>Handshake : {device['latest handshake']}</p>
                </div>
            ) )}
        </section>

    </main>
  )
}

export default ListDevices