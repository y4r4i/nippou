import {
  BrowserRouter,
  Route,
  Routes,
  useLocation,
  useNavigate,
} from "react-router-dom";
import Home from "./routes/Home";
import React, { useEffect } from "react";
import SignUp from "./routes/SignUp";
import SignIn from "./routes/SignIn";
import "antd/dist/antd.css";
import { Breadcrumb, Layout, Menu } from "antd";
import { Content, Footer, Header } from "antd/es/layout/layout";
import axios from "axios";
import { useAtom } from "jotai";
import { meAtom } from "./atoms/me";

function App() {
  const location = useLocation();
  const navigate = useNavigate();
  const [me, setMe] = useAtom(meAtom);
  axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
  axios.defaults.withCredentials = true;
  axios.defaults.xsrfCookieName = "XSRF-TOKEN";
  axios.defaults.xsrfHeaderName = "x-xsrf-token";
  useEffect(() => {
    const xsrf = async () => {
      await axios.get("/app/api/xsrf-cookie");
    };
    axios
      .get("/app/api/users/me")
      .then((value) => {
        setMe(value.data);
        if (
          location.pathname == "/sign/in" ||
          location.pathname == "/sign/up"
        ) {
          navigate("/");
        }
      })
      .catch((reason) => {
        if (reason.response.status == 401) {
          navigate("/sign/in");
        }
      });
    xsrf().then(() => {});
  }, []);

  return (
    <Layout className="layout" style={{ height: "100%" }}>
      <Header>
        <Menu theme="dark" mode="horizontal">
          <Menu.Item>{me["family_name"] + " " + me["given_name"]}</Menu.Item>
        </Menu>
      </Header>
      <Content style={{ padding: "0 50px" }}>
        <Breadcrumb style={{ margin: "16px 0" }}>
          <Breadcrumb.Item>Home</Breadcrumb.Item>
          <Breadcrumb.Item>List</Breadcrumb.Item>
          <Breadcrumb.Item>App</Breadcrumb.Item>
        </Breadcrumb>
        <div
          style={{
            minHeight: "280px",
            height: "100%",
            padding: "24px",
            background: "#fff",
          }}
        >
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/sign/up" element={<SignUp />} />
            <Route path="/sign/in" element={<SignIn />} />
          </Routes>
        </div>
      </Content>
      <Footer style={{ textAlign: "center" }}>©2023 Created by Y4r4i</Footer>
    </Layout>
  );
}

export default App;
