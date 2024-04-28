import React from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Login from './Login/index'
import PrivateRoute from './components/PrivateRoute'
import Dashboard from './Dashboard/index'

import './App.css'

function App() {

  return (
    <>

    <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/login" element={<Login />} />
        <Route  path="/dashboard" element={<PrivateRoute> <Dashboard /> </PrivateRoute>} />
      </Routes>
    </Router>
      
    </>
  )
}

export default App
