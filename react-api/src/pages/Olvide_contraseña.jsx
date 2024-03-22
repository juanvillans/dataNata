import React, { useEffect, useLayoutEffect, useState, useRef } from "react";
// import circleLogo from "../assets/img/circleLogo.png";
import { useParams } from "react-router-dom";
import Alert from "../components/Alert";
import axios from "../api/axios"; 
import ArrowRightIcon from "@mui/icons-material/ArrowRight";
import CircularProgress from "@mui/material/CircularProgress";
import TextField from "@mui/material/TextField";

// history.replaceState(null, null, location.href);

export default function Olvide_contraseña(props) {
    const { token } = useParams();
    const [isValid, setIsValid] = useState(false);
    const [status, setsTatus] = useState("Verificando...");
    const [name, setName] = useState("");
    const [successRestore, setSuccessRestore] = useState(false);
    const validateToken = async () => {
        try {
            await axios.get(`/forgot-password/${token}`).then((response) => {
                setIsValid(response.data.status);
                setName(response.data.personal_name);

                if (response.data.status == false) {
                    setsTatus("Enlace invalido ");
                }
            });
        } catch (error) {
            setsTatus("Enlace invalido ");
            setIsValid(false);
        }
    };

    useEffect(() => {
        validateToken();
    }, []);
   

    const [password, setPassword] = useState("");
    const [password2, setPassword2] = useState("");

    const [statusLoginRequest, setStatusLoginRequest] = useState(0); // 1 = loading
    const [alert, setAlert] = useState({
        open: false,
        status: "",
        message: "",
    });
    useEffect(() => {
        setTimeout(() => {
            setAlert({ open: false, message: "", status: "" });
        }, 3000);
    }, [alert.open === true]);

    const handleSubmit = async (e) => {
        e.preventDefault()

        try {
            await axios
                .post(`/restore-password`, {
                    new_password: password,
                    confirmation: password2,
                    token,
                })
                .then((response) => {
                    setAlert({
                        open: true,
                        status: "Exito",
                        message: response.data.Message || "",
                    });
                    setSuccessRestore(true);
                });
        } catch (error) {
            setAlert({
                open: true,
                status: "Error",
                message: error.response?.data?.message || "Algo salió mal",
            });
        }
    };
    const handleLogin = async () => {};
    document.querySelectorAll(".card_form input").forEach((input) => {
        if (input.type !== "file" && input.type !== "submit") {
            if (input.value) {
                input.nextElementSibling.classList.add("focus_valid");
                if (input.type === "date") {
                    input.classList.add("focus_valid");
                }
            }
            input.onfocus = () => {
                if (input.type === "date") {
                    input.classList.add("focus_valid");
                }

                input.nextElementSibling.classList.add("focus_valid");
            };
            input.onblur = () => {
                if (input.type == "date") {
                    !input.value ? input.classList.remove("focus_valid") : "";
                }
                !input.value
                    ? input.nextElementSibling.classList.remove("focus_valid")
                    : "";
            };
        } else {
            input.onchange = () => (input.style.color = "#21E6C1");
        }
    });

    return (
        <>
            {isValid === false ? (
                <p className="text-center text-lg mt-20">{status}</p>
            ) :  (
                <div className="container_login bg-dark flex justify-center items-center">
                        {successRestore === false ? 
                    (<div className="card_form min-h-screen pr-0">
                        <form onSubmit={handleLogin} className="h-screen">
                            <span className="title_and_icon mt-10">
                                <div className="background_icon">
                                    <img
                                        src={circleLogo}
                                        alt=""
                                        className="icon w-12"
                                    />
                                </div>
                                <h1 className="opacity-75">
                                    Olvidé la contraseña
                                </h1>
                            </span>
                            <p>
                                Hola {name}, aquí puedes crear una nueva clave
                            </p>
                            <div className="body_form grid gap-5">
                                <span className="mb-2 pb-0">
                                    <input
                                        type="password"
                                        id="psw1"
                                        value={password}
                                        onChange={(e) =>
                                            setPassword(e.target.value)
                                        }
                                    />
                                    <label htmlFor="psw1">
                                        Contraseña nueva{" "}
                                    </label>
                                </span>

                                <span className="mb-2 pb-0">
                                    <input
                                        type="password"
                                        id="psw2"
                                        value={password2}
                                        onChange={(e) =>
                                            setPassword2(e.target.value)
                                        }
                                    />
                                    <label htmlFor="psw2">
                                        Repita la contraseña{" "}
                                    </label>
                                </span>
                            </div>
                            <button
                                type="submit"
                                onClick={handleSubmit}
                                className={`rounded-r-full btn_submit mt-5 top-96  pt-3 pb-1 disabled  -left-20 ${
                                    password.trim() === password2.trim() &&
                                    password.length > 1
                                        ? "active"
                                        : "disabled"
                                }`}
                            >
                                {!statusLoginRequest ? (
                                    <div>
                                        Continuar <ArrowRightIcon />
                                    </div>
                                ) : (
                                    <div className="flex text-right justify-end gap-8">
                                        Cargando...
                                        <div className="relative -top-0.5">
                                            <CircularProgress
                                                color="inherit"
                                                size={33}
                                            />
                                        </div>
                                    </div>
                                )}
                            </button>
                        </form>
                        
                    </div>)
                    : (
                        <div className="flex items-center justify-center h-screen ">
                            <div>
                                <p className="text-white font-bold text-xl">¡Su contraseña ha sido cambiada exitosamente!</p>
                                <a href="/login" className="text-3xl p-4 mt-4 block text-center w-100 m-auto bg-green pt-5">Ir al logín</a>

                            </div>
                        </div>
                    )
                }
                    <Alert
                        open={alert.open}
                        status={alert.status}
                        message={alert.message}
                    />
                </div>
                )}
            
        </>
    );
}
