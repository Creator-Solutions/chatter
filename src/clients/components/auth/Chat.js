import React, { useState, useEffect } from "react";
import { useLocation, useNavigate } from "react-router";
import back from "../Images/back.png";
import pfp from "../Images/pfp.png";
import "../Styling/Chat.css";

const Chat = () => {
  const [chatroom, setChatRoom] = useState("");
  const [chats, setChats] = useState([]);
  const [users, setUsers] = useState([]);
  const [error, SetError] = useState("");
  const [message, setMessage] = useState("");
  const [loaded, setLoaded] = useState(false);
  const { state } = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    setChatRoom(state.Room);

    const API = "http://localhost/chatter/src/server/auth/Room.php";
    let room_ID = state.Room_ID;

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Room_ID: room_ID,
        Type: "Users",
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
        setUsers(data);
      })
      .catch((err) => {
        SetError(err);
        console.log(err);
      });
    Get_Messages();
  }, []);

  let Get_Messages = () => {
    const API = "http://localhost/chatter/src/server/auth/Messages.php";
    let room_ID = state.Room_ID;

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Room_ID: room_ID,
        Type: "Messages",
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
        setChats(data);
      })
      .catch((err) => {
        SetError(err);
        console.log(err);
      });
  };

  let Get_Time_Stamp = () => {
    let date = new Date();
    var curr_hour = date.getHours();

    let daytime = curr_hour < 12 ? "AM" : "PM";

    let time = `${date.getHours()}:${date.getMinutes()} ${daytime}`;

    return time;
  };

  let Create_Chat = () => {
    const API = "http://localhost/chatter/src/server/auth/Messages.php";

    const requestOptions = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        Room_ID: state.Room_ID,
        UUID: state.UUID,
        Timestamp: Get_Time_Stamp(),
        Message: message,
        Type: "Send",
      }),
    };

    fetch(API, requestOptions)
      .then((response) => {
        if (response.ok) return response.json();
        else {
          SetError(response.error);
          console.log(error);
          return {};
        }
      })
      .then((data) => {
        if (data[0].Message == "Success") {
          let msg = {
            FullName: state.Name,
            Timestamp: Get_Time_Stamp(),
            Message: message
          };
          setChats(chats => [...chats, msg]);
        }
      })
      .catch((err) => {
        SetError(err);
      });   
  };

  return (
    <div className="chatbox">
      <div className="sideBar">
        {users.map((user, index) => {
          return (
            <div key={index} className="userCard">
              <img src={pfp} alt="pfp" />
              <p>{user.UserName}</p>
            </div>
          );
        })}
      </div>
      <div className="middle">
        <div className="topBox">
          <img src={back} alt="back" onClick={() => navigate(-1)} />
          <h1>Welcome To The {chatroom}'s Room</h1>
        </div>
        <div className="msgBox">
          {chats.map((item, index) => {
            return (
              <div key={index} className="msgContainer">
                <div className="userData">
                  <img src={pfp} alt="pfp" />
                  <p className="name">{item.FullName}</p>
                  <p className="timestamp">{item.Timestamp}</p>
                </div>
                <div className="message">
                  <p className="message_content">{item.Message}</p>
                </div>
              </div>
            );
          })}
        </div>
        <div className="input">
          <input
            type="text"
            placeholder="Type Something..."
            value={message}
            onChange={(e) => setMessage(e.target.value)}
          />
          <button onClick={() => Create_Chat()}>Send</button>
        </div>
      </div>
    </div>
  );
};

export default Chat;
