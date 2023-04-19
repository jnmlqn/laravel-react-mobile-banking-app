import React, { Component } from 'react';
import ApiProvider from '../providers/ApiProvider';
import ReactDOM from 'react-dom';
import Login from './Login'
import SendToUser from './SendToUser'
import SendToBank from './SendToBank'
import Transactions from './Transactions'
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class Home extends React.Component {
    constructor() {
        super();
        this.api = new ApiProvider;
        this.state = {
            name: this.api.getCookie('name'),
            balance: this.api.getCookie('balance'),
            transactions: []
        }
    }

    componentDidMount() {
        this.refreshUserData();
    }

    refreshUserData() {
        this.api.post('me')
        .then(({data: {message, data}}) => {
            this.api.setCookie('balance', data.balance);
            this.setState({
                balance: this.api.getCookie('balance'),
                name: this.api.getCookie('name'),
            });
        })
        .catch((error) => {
            switch(error.response.status) {
                case 401:
                    toast.error('Session expired');
                    this.redirectToLogin();
                    break;
                default:
                    toast.error(error.response.statusText);
                    break;
            }
        });
    }

    redirectToLogin() {
        ReactDOM.render(<Login />, document.getElementById('application'));
    }

    sendToUser() {
        ReactDOM.render(<SendToUser />, document.getElementById('application'));
    }

    sendToBank() {
        ReactDOM.render(<SendToBank />, document.getElementById('application'));
    }

    logout() {
        this.api.post('logout')
        .then(({data: {message}}) => {
            toast.success(message);
            this.api.setCookie('token', '');
            this.api.setCookie('balance', '');
            this.api.setCookie('name', '');
            this.redirectToLogin();
        })
        .catch((error) => {
            this.api.setCookie('token', '');
            this.api.setCookie('balance', '');
            this.api.setCookie('name', '');
            this.redirectToLogin();
        });
    }

    render() {
        return (
          <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-6 col-12 mb-2">
                        <div className="card">
                            <div className="card-header">
                                <a href="#" onClick={this.logout.bind(this)} className="text-primary" style={{float: 'right'}}>
                                    Logout
                                </a>
                                Welcome, {decodeURIComponent(this.state.name)}
                            </div>

                            <div className="card-body">
                                <div>
                                    <div className="container border mb-4 p-4">
                                        Available Balance: ₱ {decodeURIComponent(this.state.balance)}
                                    </div>
                                    <p>
                                        <button
                                            className="btn btn-primary w-100"
                                            onClick={this.sendToUser.bind(this)}
                                        >
                                            Send money to user
                                        </button>
                                    </p>
                                    <p>
                                        <button
                                            className="btn btn-primary w-100"
                                            onClick={this.sendToBank.bind(this)}
                                        >
                                            Send money to bank
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-4 col-12 mb-2">
                        <div className="card">
                            <div className="card-header">Transaction History</div>

                            <div className="card-body" style={{height: '80vh', overflowY: 'scroll'}}>
                                <Transactions />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Home;