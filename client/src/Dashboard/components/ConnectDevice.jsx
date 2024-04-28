import React from 'react'
import { FiPlusCircle } from "react-icons/fi";
import { IoMdRemoveCircleOutline } from "react-icons/io";
import { FaRegRectangleList } from "react-icons/fa6";

function ConnectDevice({changeComponent}) {
  return (
    <main className='flex flex-wrap items-center justify-center h-screen gap-10 montserrat '>
        <button onClick={ () => changeComponent('addDevices')} className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <FiPlusCircle className='w-10 h-10' />
            <p className='text-lg font-bold '>Add devices</p>
        </button>

        <button onClick={ () => changeComponent('listDevices')} className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <FaRegRectangleList className='w-10 h-10' />
            <p className='text-lg font-bold '>List Devices</p>
        </button>

        <button onClick={ () => changeComponent('removeDevices')} className='bg-gray-200 rounded-lg py-10 px-20 flex flex-col items-center justify-center gap-5'>
            <IoMdRemoveCircleOutline className='w-10 h-10' />
            <p className='text-lg font-bold '>Remove Devices</p>
        </button>
    </main>
  )
}

export default ConnectDevice