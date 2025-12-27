compose_command = docker compose
db_compose_file = -f docker-compose.db.yaml
db_service = postgres

run_db:
	$(compose_command) $(db_compose_file) \
		up -d --force-recreate --build

destroy_db:
	$(compose_command) $(db_compose_file) \
		down -v --remove-orphans

psql:
	$(compose_command) $(db_compose_file) \
		exec -it $(db_service) \
		psql -U $(user)
