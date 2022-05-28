import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import './Styling/Header.css';
import Home from './HomePage';
import Login from "./auth/Login";
import Signup from './auth/SignUp';
import Dashboard from './user/Dashboard';
import Chat from './auth/Chat';

const Header = () => {
  return (
    <Router>
      <div className="parent">
        <div className="nav">
          <Link to="/">Home</Link>
          <Link to="/login">Login</Link>
          <Link to="/signup">Sign Up</Link>
        </div>
        <Routes>
          <Route path="/" element={<Home />}/>
          <Route path="/login" element={<Login />}/>
          <Route path="/signup" element={<Signup />}/>
          <Route path="/dash" element={<Dashboard />}/>
          <Route path="/chat/:room" element={<Chat />}/>
        </Routes>
      </div>
    </Router>
  );
}

export default Header;
