.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: composer_install
composer: ## [host] Installe les dépendances composer
	docker compose run composer update

.PHONY: test
test: ## [host] Lance les tests
	docker compose run php vendor/bin/phpcs
	docker compose run php vendor/bin/phpstan
	docker compose run php vendor/bin/phpunit

.PHONY: _test
_test: ## [container] Lance les tests
	vendor/bin/phpcs
	vendor/bin/phpstan
	vendor/bin/phpunit

permissions-dev: ## [host] Configure les permissions de dev
	sudo setfacl -R  -m u:$(USER):rwX ./
	sudo setfacl -dR -m u:$(USER):rwX ./
