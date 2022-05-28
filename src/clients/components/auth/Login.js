import React, { useState } from "react";
import { Link, useNavigate  } from "react-router-dom";
import "../Styling/Login.css";


const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, SetError] = useState("");
  let navigate = useNavigate();

  let Authenticate = (props) => {
    const API = "http://localhost/chatter/src/server/auth/OAuth.php";
    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Email: email,
        Password: password,
        Type: "Login",
      }),
    };

    fetch(API, requestOptions)
      .then((response) => {
        if (response.ok) return response.json();
        else {
          SetError(response.error);
          return {};
        }
      })
      .then((data) => {
        console.log(data[0]);
        
        switch (data[0].Message){
          case 'Authenticated':
            navigate('/dash', {state: data[0]});
            break;
          case 'Incorrect Password':
            break;
          case 'Account Not Found':
            SetError('Account Not Found');
            break;
          default:
            break;
        }
      });
  };

  return (
    <div className="main">
      <h1>Login</h1>
      <div className="box">
        <p>{error}</p>
      </div>
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
        <input type="button" value="Login" onClick={Authenticate} />
        <div className="links">
          <Link to="/forgotPass">Forgot Password</Link>
          <Link to="/signup">Don't have an account? Sign Up</Link>
        </div>
      </div>
    </div>
  );
};

export default Login;
