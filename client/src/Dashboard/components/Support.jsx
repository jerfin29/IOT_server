import React from 'react'
import {Link} from 'react-router-dom'
import { FaGithub } from "react-icons/fa";
import { BsInstagram } from "react-icons/bs";
import { IoLogoSlack } from "react-icons/io";

function Support() {
  return (
    <main className='flex flex-wrap items-center justify-center h-screen gap-10 montserrat '>
        <Link to="https://github.com" className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <FaGithub className='w-10 h-10' />
        </Link>

        <Link to="https://instagram.com" className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <BsInstagram className='w-10 h-10' />
        </Link>

        <Link to="https://slack.com" className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <IoLogoSlack className='w-10 h-10' />
        </Link>
    </main>
  )
}

export default Support