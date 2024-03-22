import axios from "./api/axios";

import { Route, Routes, useNavigate } from "react-router-dom";
import { useEffect, useState, cloneElement, lazy, Suspense } from "react";
import { useLocation } from "react-router-dom";

import TopNavbar from "./layouts/TopNavbar";
import Nav from "./layouts/nav";
import Footer from "./layouts/Footer";
import ConfirmModal from "./components/ConfimModal";
// import Alert from "../components/Alert";

const Usuarios = lazy(() => import("./pages/Usuarios"));
// const Dashboard = lazy(() => import("./pages/Dashboard"))
// const Asistencia = lazy(() => import("./pages/Asistencia"))
// const Personal = lazy(() => import("./pages/Personal"))
const Productos = lazy(() => import("./pages/Productos"));
const Inventario = lazy(() => import("./pages/Inventario"));
const Entradas = lazy(() => import("./pages/Entradas"));
const Salidas = lazy(() => import("./pages/Salidas"));
const Organizaciones = lazy(() => import("./pages/Organizaciones"));
const Cambiar_contraseña = lazy(() => import("./pages/Cambiar_contraseña"));
// const Pagos = lazy(() => import("./pages/Pagos"))

// console.log(userData)
export default function app() {
  const navigate = useNavigate();
  const userData = JSON.parse(localStorage.getItem("userData"));

  const [navStatus, setNavStatus] = useState(true);
  // const [pressed, setPressed] = useState(false)
  const location = useLocation();
  useEffect(() => {
    if (userData) {
      console.log(userData);
    } else {
      navigate("/");
    }
    // checkSession()
  }, []);
  const [html, setHtml] = useState("");
  const [session, setSession] = useState({ status: false });

  const [modalConfirm, setModalConfirm] = useState({
    isOpen: false,
    modalInfo: false,
  });
  const [permited, setPermited] = useState(true);

  return (
    <div className="dashboard_container">
      <Nav
        getNavStatus={() => setNavStatus((prev) => !prev)}
        status={navStatus}
        userData={userData}
      />
      <div className={`top_nav_and_main`}>
        <div
          className={`mainDashboard_container ${navStatus ? "small" : "large"}`}
        >
          <TopNavbar userData={userData} />
          <main>
            <Suspense>
              <Routes forceRefresh={true}>
                <Route
                  key={"Cambiar_contraseña"}
                  path="/Cambiar_contraseña"
                  element={<Cambiar_contraseña userData={userData} />}
                ></Route>
                {/* {session.permission.map(mod =>{
                      const module_import = cloneElement(pages[mod.module_name.replaceAll(' ', '_')], {permissions: mod.permissions})

                        return (
                          <Route exact path={mod.module_url} element={module_import} key={mod.module_url}></Route>
                          
                        )
                    })} */}
                {/* <Route key={'cambiarContaseña'} path="/cambiar_contraseña" element={<Cambiar_contraseña  userData={userData}/>}></Route> */}
                {userData.permissions[1] && (
                  <Route
                    key={"Organizaciones"}
                    path="/Organizaciones"
                    element={<Organizaciones userData={userData} />}
                  ></Route>
                )}

                {userData.permissions[2] && (
                  <Route
                    key={"usuarios"}
                    path="/usuarios"
                    element={<Usuarios userData={userData} />}
                  ></Route>
                )}

                {userData.permissions[3] && (
                  <Route
                    key={"productos"}
                    path="/Productos/*"
                    element={<Productos userData={userData} />}
                  ></Route>
                )}

                {userData.permissions[4] && (
                  <Route
                    key={"Entradas"}
                    path="/Entradas/"
                    element={<Entradas userData={userData} />}
                  ></Route>
                )}

                {userData.permissions[5] && (
                  <Route
                    key={"Salidas"}
                    path="/Salidas/"
                    element={<Salidas userData={userData} />}
                  ></Route>
                )}

                {userData.permissions[6] && (
                  <Route
                    key={"Inventario"}
                    path="/Inventario/"
                    element={<Inventario userData={userData} />}
                  ></Route>
                )}

                {/* <Route key={'Contacto'} path="/Contacto" element={<Contacto  userData={userData}/>}></Route> */}
              </Routes>
            </Suspense>
          </main>

          <Footer></Footer>
        </div>
      </div>
      <ConfirmModal
        closeModal={() => {
          setModalConfirm({ isOpen: false });
          // setRowSelected([])
        }}
        modalInfo={modalConfirm.modalInfo}
        isOpen={modalConfirm.isOpen}
        aceptFunction={() => modalConfirm.aceptFunction()}
      />
    </div>
  );
}
