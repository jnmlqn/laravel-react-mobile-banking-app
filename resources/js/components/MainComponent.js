import ApiProvider from '../providers/ApiProvider';
import Home from './Home'
import Login from './Login'
import React from 'react';
import ReactDOM from 'react-dom';

if (document.getElementById('application')) {
	const api = new ApiProvider;

	if (api.getCookie('token')) {
		ReactDOM.render(<Home />, document.getElementById('application'));
	} else {
    	ReactDOM.render(<Login />, document.getElementById('application'));
	}

}