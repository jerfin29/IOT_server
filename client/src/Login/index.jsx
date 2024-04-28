import React, {useState} from "react";
import axios from 'axios';
import Logo from '../assets/Logo.png'

const Login = () => {

    const [form, setForm] = useState({
        username :"",
        password:""
    });

    const handleOnChange = (e) => {
        setForm({
            ...form,
            [e.target.name]: e.target.value
        });
    };

    const handleOnSubmit = (e) => {
        e.preventDefault();
        console.log(form);
        axios.post('http://94.237.79.8/api/auth/login',form,{
            headers: {
                'Access-Control-Allow-Origin': '*',
            }
        
        })
        .then( (response) => {
            console.log(response.data)
            localStorage.setItem('token',response.data.tokens.access_token);
        })
    };

    

    return (
        <main className="h-screen flex bg-gray-200  items-center justify-center  montserrat">
            
            <section className="flex flex-col justify-center bg-white rounded-lg py-10 px-10 items-center w-auto gap-10">

                <img src={Logo} alt="Logo" />

                <form onSubmit={handleOnSubmit} className="flex flex-col gap-5">

                    <div className="flex flex-col gap-2">
                        <label htmlFor="username">User Name *</label>
                        <input value={form.username} onChange={handleOnChange} className="border-2 rounded-md h-10 w-72 px-3" type="text" id="username" required name="username" />
                    </div>

                    <div className="flex flex-col gap-2">
                        <label htmlFor="password">Password</label>
                        <input value={form.password} onChange={handleOnChange} className="border-2 rounded-md h-10 px-3" type="password" id="password" required name="password" />
                    </div>

                    <button className="bg-blue-500 rounded-lg text-white p-2 mx-24" type="submit">Login</button>
                    
                </form>

                <p>Don't have an account ? Signup</p>
        
            </section>

        </main>
    );
    };

export default Login;