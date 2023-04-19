import React, { Component, useRef } from 'react';
import Home from './Home'
import ReactDOM from 'react-dom';
import ApiProvider from '../providers/ApiProvider';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Select from 'react-select'

class SendToUser extends React.Component {
    constructor() {
        super();
        this.api = new ApiProvider;
        this.state = {
            banks: [],
            selectedProvider: {},
            selectedBank: {},
            providerOptions: [
                {
                    value: 'pesonet',
                    label: 'Pesonet',
                },
                {
                    value: 'instapay',
                    label: 'Instapay',
                }
            ],
            bankOptions: [],
            amount: 0,
            bank: ''
        }
    }

    componentDidMount() {
        this.refreshUserData();
    }

    refreshUserData() {
        this.api.get('banks')
        .then(({data: {message, data}}) => {
            this.setState({
                banks: data
            });
            const banks = data.filter((bank) => {
                return bank.provider === this.state.selectedProvider.value;
            });

            this.setState({
                bankOptions: banks
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

    handleSubmit() {
        event.preventDefault();

        const sendPaymentData = {
            type: 'send',
            mode: 'bank',
            amount: this.state.amount,
            bank: this.state.bank
        }

        this.api.post('transaction/transfer', sendPaymentData)
        .then(({data: {message, data}}) => {
            toast.success(message);
            this.setState({
               email: '',
               amount: 0,
               selectedProvider: {},
               selectedBank: {},
               bankOptions: [],
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

    showBanks(event) {
        const banks = this.state.banks.filter((bank) => {
            return bank.provider === event.value;
        });

        this.setState({
            selectedProvider: event,
            bankOptions: banks.map((bank) => {
                return {
                    value: bank.id,
                    label: bank.bank,
                }
            })
        });
    }

    selectBank(event) {
        this.setState({
            selectedBank: event,
            email: '',
            amount: 0,
            bank: event.value,
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
                            <div className="card-header">Send to bank</div>
                            <div className="card-body">
                                <form onSubmit={this.handleSubmit.bind(this)}>
                                    <label>Provider</label>
                                    <Select
                                        options={this.state.providerOptions}
                                        onChange={this.showBanks.bind(this)}
                                        value={this.state.selectedProvider}
                                    />

                                    <p></p>

                                    <label>Bank</label>
                                    <Select
                                        options={this.state.bankOptions}
                                        onChange={this.selectBank.bind(this)}
                                        value={this.state.selectedBank}
                                    />

                                    <p></p>

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