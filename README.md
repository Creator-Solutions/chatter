
# Chatter ![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/creator-solutions/chatter) ![GitHub all releases](https://img.shields.io/github/downloads/creator-solutions/chatter/total)

 Official repository for Chatter Application written in ReactJS and PHP
 
 To limit PHP warnings and issues a version from 7.14 and higher is recommended
 
 # About the project
 
I've always been fascinated with building or creating things from scatch. I'm also intrigued about the science behind communication applications, as well as server
client communications. It has led me to the creation of Chatter, a communications platform built with a server-side middle-man and MySQL as a storage service. 
 
The web-application uses server-side authentication by providing the user with an access token, provided that credentials match, that is required to finalize the login attemtps. User account are validated by credentials, such as an email address and password. The password is stored using argon2ID encryption and is the main aspect of authentication. 
 
# Tech Stack
- [ ] PHP => 7.4.29
- [ ] ReactJs => 18.1.0
- [ ] Node => 16.14.0
- [ ] CSS


# Running the application

- [x] Setting up XAMPP

1. Download XAMPP installer from `https://www.apachefriends.org/index.html`.
2. Open the installer and install XAMPP to `C:/` drive or any other drive you have.
3. Navigate to `C:/XAMPP/htdocs and create a `Chatter` Folder.
4. Clone the repo into the new folder.
5. Open the `XAMPP Control Panel and start` `Apache` and `MySQL`
6. Press `Admin` on both services.

- [x] Setting Up ReactJS

1. Navigate to the src/client Folder and run the following command `yarn create react-app client`
2. Once done, drag the components folder into the source folder of the chatter directory, like the image below
![image](https://user-images.githubusercontent.com/54483520/170872168-f0e032c4-724b-47d2-9e43-f8ed1b3bba81.png)

3. Open App.js folder and add the following Code.

![app](https://user-images.githubusercontent.com/54483520/170872334-23403d56-7928-4e45-aa77-0bc2c3bcf9b3.png)

4. To start the ReactJS App run the following command `yarn start`

![yarn](https://user-images.githubusercontent.com/54483520/170872474-477ce914-f8e5-492c-b746-01623d5aaf17.png)


 
