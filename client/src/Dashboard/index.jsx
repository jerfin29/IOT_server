import React,{useState} from "react";
import Sidebar from "./components/Sidebar"
import Logout from "./components/Logout";
import ConnectDevice from "./components/ConnectDevice";
import AddDevices from "./components/AddDevices";
import ListDevices from "./components/ListDevices";
import RemoveDevices from "./components/RemoveDevices";
import ControlPanel from "./components/ControlPanel";
import Support from "./components/Support";

const Dashboard = () => {

    const [currentComponent, setCurrentComponent] = useState('connectDevice');

    const changeComponent = (component) => {
        setCurrentComponent(component);
    }   

    return (
        <main className="flex items-start w-screen">
            <section className=" w-2/12">
                <Sidebar changeComponent = {changeComponent} />
            </section>

            <section className="w-10/12  h-auto">

                {currentComponent === null && <h1 className="text-2xl text-center mt-10">Welcome to Dashboard</h1>}
                
                {currentComponent === 'connectDevice' && <ConnectDevice changeComponent = {changeComponent} />}
                {currentComponent === 'addDevices' && <AddDevices />}
                {currentComponent === 'listDevices' && <ListDevices />}
                {currentComponent === 'removeDevices' && <RemoveDevices />}
                {currentComponent === 'controlPanel' && <ControlPanel />}
                {currentComponent === 'support' && <Support />}
                {currentComponent === 'logout' && <Logout />}

            </section>
        </main>
    )
}

export default Dashboard