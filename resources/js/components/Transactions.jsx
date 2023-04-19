import React, { Component } from 'react';
import ApiProvider from '../providers/ApiProvider';
import ReactDOM from 'react-dom';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

class Transaction extends React.Component {
    constructor() {
        super();
        this.api = new ApiProvider;
        this.state = {
            transactions: []
        }
    }

    componentDidMount() {
        this.getTransactionHistory();
    }

    getTransactionHistory() {
        this.api.get('transaction/history')
        .then(({data: {message, data}}) => {
            this.setState({
                transactions: data
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

    render() {
        let transactions = this.state.transactions.map((transaction, key) => {
            return (
                <div key={key} className="container border mb-2 p-4">
                    <span style={{float: 'right'}}>
                        - ₱{ transaction.amount }
                    </span>
                    { transaction.description }
                    <div className="mb-2">
                        <p className="small" style={{float: 'right'}}>
                            Last balance: ₱{ transaction.last_current_balance }
                        </p>
                    </div>
                </div>
            )
        });

        return transactions;
    }
}

export default Transaction;