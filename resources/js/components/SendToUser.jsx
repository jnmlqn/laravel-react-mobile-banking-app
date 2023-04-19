import React, { Component, useRef } from 'react';
import Home from './Home'
import ReactDOM from 'react-dom';
import ApiProvider from '../providers/ApiProvider';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class SendToUser extends React.Component {
    constructor() {
        super();
        this.api = new ApiProvider;
        this.state = {
            type: 'send',
            mode: 'email',
            email: '',
            amount: 0
        }
    }

    handleSubmit() {
        event.preventDefault();

        this.api.post('transaction/transfer', this.state)
        .then(({data: {message, data}}) => {
            toast.success(message);
            this.setState({
                email: '',
                amount: 0,
            });
        })
        .catch((error) => {
            switch(error.response.status) {
                case 401:
                    toast.error('Session expired');
                    this.redirectToLogin();
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
                    toast.error(error.response.data.message);
                    break;
            }
        });
    }

    handleChange(event) {
        this.setState({
            [event.target.id]: event.target.value
        });
    }

    close() {
        ReactDOM.render(<Home />, document.getElementById('application'));
    }

    render() {
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-6 col-12">
                        <div className="card">
                            <div className="card-header">Send to user</div>
                            <div className="card-body">
                                <form onSubmit={this.handleSubmit.bind(this)}>
                                    <p>
                                        <label htmlFor="email">Recipient's email address</label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            name="email"
                                            id="email"
                                            onChange={this.handleChange.bind(this)}
                                            value={this.state.email}
                                            required
                                        />
                                    </p>
                                    <p>
                                        <label htmlFor="amount">Amount</label>
                                        <input
                                            type="number"
                                            className="form-control"
                                            name="amount"
                                            id="amount"
                                            onChange={this.handleChange.bind(this)}
                                            value={this.state.amount}
                                            required
                                        />
                                    </p>
                                    <div style={{float: 'right'}}>
                                        <button className="btn btn-success">Transfer</button> &nbsp;
                                        <button type="button" onClick={this.close.bind(this)} className="btn btn-danger">Close</button>
                                    </div>
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

export default SendToUser;