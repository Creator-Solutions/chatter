import React, { useState } from "react";
import { Link } from "react-router-dom";
import "../Styling/Signup.css";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPass, setConfirmPass] = useState("");
  const [error, SetError] = useState("");

  let Click = () => {
    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Email: email,
        Password: password,
        Type: "Login",
      }),
    };

    fetch("", requestOptions)
    .then((response) => {
        if (response.ok) return response.json();
        else {
            SetError(response.error);
            return {};
        }
    })
  };

  return (
    <div className="main">
      <h1>Login</h1>
      <div className="form">
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <input
          type="password"
          placeholder="Confirm Password"
          value={confirmPass}
          onChange={(e) => setConfirmPass(e.target.value)}
        />
        <input type="button" value="Sign Up" />
        <div className="links">
          <Link to="/forgotPass">Forgot Password</Link>
          <Link to="/noAcc">Don't have an account? Sign Up</Link>
        </div>
      </div>
    </div>
  );
};

export default Login;
