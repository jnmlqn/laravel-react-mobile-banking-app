<h2>HOW TO RUN THE APPLICATION</h2>
<ul>
	<li>
		Run <b>docker-compose up -d</b>
	</li>
	<li>
		Run <b>docker exec -it app bash</b>
	</li>
	<li>
		Run <b>php artisan doctrine:schema:create</b>
	</li>
	<li>
		Run <b>php artisan import:users</b>
	</li>
	<li>
		Run <b>php artisan test</b>
	</li>
	<li>
		Open <b>http://localhost:4001</b> in your browser
	</li>
</ul>
