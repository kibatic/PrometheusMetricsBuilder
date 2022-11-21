.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: composer_install
composer_install: ## [host] Installe les dépendances composer
	docker compose run composer install

.PHONY: test
test: ## [host] Lance les tests
	docker compose run php vendor/bin/phpunit test/MetricTest.php

permissions-dev: ## [host] Configure les permissions de dev
	sudo setfacl -R  -m u:$(USER):rwX ./
	sudo setfacl -dR -m u:$(USER):rwX ./
