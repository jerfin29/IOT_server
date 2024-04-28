import React,{useState} from 'react'
import axios from 'axios';

function AddDevices() {

    const [form, setForm] =useState({ email : "", publicKey : "" });

    const token = localStorage.getItem('token');

    const handleOnChange = (e) => {
        setForm({
            ...form,
            [e.target.name]: e.target.value
        });
    }

    const handleOnSubmit = (e) => {
        e.preventDefault();
        console.log(form);
        axios.post('http://94.237.79.8/api/wg/add_peer',form,{
            headers:{
                'Authorization':token,
            }
        })
        .then( (response) => {
            console.log(response.data)
        })
        
        setForm({ email : "", publicKey : "" });

    }

  return (
    <main className="h-screen flex bg-gray-100  items-center justify-center  montserrat">
            
            <section className="flex flex-col justify-center bg-white rounded-lg py-10 px-10 items-center w-auto gap-10">


                <form onSubmit={handleOnSubmit} className="flex flex-col gap-5">

                    <div className="flex flex-col gap-2">
                        <label htmlFor="email" className='font-semibold'>Email Id</label>
                        <input value={form.email} onChange={handleOnChange} className="border-2 rounded-md h-10 w-72 px-3" type="email" id="email" required name="email" />
                    </div>

                    <div className="flex flex-col gap-2">
                        <label htmlFor="publicKey" className='font-semibold'>Public Key</label>
                        <input value={form.publicKey} onChange={handleOnChange} className="border-2 rounded-md h-10 px-3" type="text" id="publicKey" required name="publicKey" />
                    </div>

                    <button className="bg-blue-500 rounded-lg text-white p-2 mx-24 font-semibold" type="submit">Add</button>
                    
                </form>

        
            </section>

        </main>
  )
}

export default AddDevices