export default class ApiProvider {
	post(url, data) {
		return window.axios.post(`/api/v1/${url}`, data, this.setConfig());
	}

	get(url) {
		return window.axios.get(`/api/v1/${url}`);
	}

	setConfig() {
		const token = this.getCookie('token');
		let config = null;

		if (token !== null && token !== '') {
			config = {
				headers: {
					Authorization: `Bearer ${token}`
				}
			}
		}

		return config;
	}

	getCookie(cookieName) {
		const name = cookieName + '=';
		const cookies = document.cookie.split(';');
		for(let i = 0; i < cookies.length; i++) {
			let c = cookies[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}

		return null;
	}

	setCookie(cookieName, cookieValue, expDays) {
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + expDays);
		var c_value = escape(cookieValue) + ((expDays == null) ? '' : '; expires=' + exdate.toUTCString());
		document.cookie = cookieName + '=' + c_value;
	}
}