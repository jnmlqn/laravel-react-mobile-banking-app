import React, { Component, useRef } from 'react';
import Home from './Home'
import ReactDOM from 'react-dom';
import ApiProvider from '../providers/ApiProvider';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class Login extends React.Component {
    constructor() {
        super();
        this.api = new ApiProvider;
        this.state = {
            email: '',
            password: ''
        }
    }

    handleSubmit() {
        event.preventDefault();

        this.api.post('login', this.state)
        .then(({data: {message, data}}) => {
            this.api.setCookie('token', data.access_token);
            this.api.setCookie('balance', data.balance);
            this.api.setCookie('name', data.name);
            ReactDOM.render(<Home />, document.getElementById('application'));
        })
        .catch((error) => {
            switch(error.response.status) {
                case 401:
                    toast.error('Invalid email or password');
                    break;
                case 422:
                    const validationMessage = error.response.data.data;

                    let html = ``;

                    for (const key in validationMessage) {
                        for (const msg of validationMessage[key]) {
                            html += `${msg}\n`
                        }
                    }

                    toast.warn(html);
                    break;
                default:
                    toast.error(error.response.statusText);
                    break;
            }
        });
    }

    handleChange(event) {
        this.setState({
            [event.target.id]: event.target.value
        });
    } 

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Login</div>
                            <div className="card-body">
                                <form onSubmit={this.handleSubmit.bind(this)}>
                                    <p>
                                        <label htmlFor="email">Email address</label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            name="email"
                                            id="email"
                                            onChange={this.handleChange.bind(this)}
                                            required
                                        />
                                    </p>
                                    <p>
                                        <label htmlFor="password">Password</label>
                                        <input
                                            type="password"
                                            className="form-control"
                                            name="password"
                                            id="password"
                                            onChange={this.handleChange.bind(this)}
                                            required
                                        />
                                    </p>
                                    <p style={{float: 'right'}}>
                                        <button className="btn btn-primary">Login</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <ToastContainer
                    autoClose={3000}
                />
            </div>
        );
    }
}

export default Login;