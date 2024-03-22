import React, { lazy, Suspense } from "react";
import { Route, Routes } from "react-router-dom";

// import "../css/basics.css";
const Config_products = lazy(() => import("./Config_products"));
const Products_crud = lazy(() => import("./Products_crud"));

export default function Usuarios(props) {
  return (
    <>
    <Suspense>
      <Routes forceRefresh={true}>
        <Route
          key={"productos"}
          path="/"
          element={<Products_crud userData={props.user_data} />}
        ></Route>
        <Route
          key={"config_products"}
          path="/config_products"
          element={<Config_products userData={props.user_data} />}
        ></Route>
        {/* <Route key={'Contacto'} path="/Contacto" element={<Contacto  userData={session.user_data}/>}></Route> */}
      </Routes>
    </Suspense>
    </>
  );
}
