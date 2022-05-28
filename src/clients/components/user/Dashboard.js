import React, { useEffect, useState } from "react";
import { useLocation, useNavigate } from "react-router";
import "../Styling/Dashboard.css";
import ghost from "../Images/pf.png";

const Dashboard = () => {
  const [error, SetError] = useState("");
  const [room, setRoom] = useState([]);
  const { state } = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    const API = "http://localhost/chatter/src/server/auth/Room.php";

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Type: "Room",
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
        if (data) {
          setRoom(data);
        } else {
          SetError(data.error);
          console.log(error);
        }
      })
      .catch((err) => {
        SetError(err);
        console.log(err);
      });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  let Join_Room = (id, name) => {
    const API = "http://localhost/chatter/src/server/auth/Room.php";

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Name: state.Name,
        UUID: state.UUID,
        ID: id,
        Type: "Join",
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
        if (data[0].Message === "Joined") {
          let user_state = {
            Name: state.Name,
            UUID: state.UUID,
            Room_ID: id,
            Room: name
          };
          navigate(`/chat/${name}`, { state: user_state });
        } else {
          SetError(data.error);
          console.log(error);
        }
      })
      .catch((err) => {
        SetError(err);
        console.log(err);
      });
  };

  return (
    <div className="container">
      <h1 className="title">Welcome {state.Name}</h1>

      <div className="rooms">
        {room.map((item) => {
          return (
            <div className="card" key={item.Room_ID}>
              <img src={ghost} alt="ghost" />
              <button onClick={() => Join_Room(item.Room_ID, item.Room_Name)}>
                Join {item.Room_Name}
              </button>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default Dashboard;
