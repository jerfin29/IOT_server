import React from "react";
import { MdAddChart } from "react-icons/md";
import { BsBuildings } from "react-icons/bs";
import { RiLogoutCircleLine } from "react-icons/ri";
import { MdOutlineSupportAgent } from "react-icons/md";
import Logo from '../../assets/Logo.png'

const Sidebar = ({changeComponent}) => {
    return(

        <section className="flex flex-col base-font px-10 py-10 gap-10 bg-gray-200 h-screen montserrat">
                <img src={Logo} className="w-28" alt="logo" />
                <button onClick={() => changeComponent('connectDevice')} className="flex gap-2 items-center font-semibold"> <MdAddChart className="w-6 h-6" /> Connect Device</button>
                <button onClick={() => changeComponent('controlPanel')} className="flex gap-2 items-center font-semibold"><BsBuildings className="w-6 h-6" />Control Panel</button>
                <button onClick={() => changeComponent('support')} className="flex gap-2 items-center font-semibold"><MdOutlineSupportAgent className="w-6 h-6" />Support</button>
                <button onClick={() => changeComponent('logout')} className="flex gap-2 items-center font-semibold"><RiLogoutCircleLine className="w-6 h-6" />Logout</button>
        </section>

    )

}

export default Sidebar