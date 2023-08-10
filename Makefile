.PHONY: init
init:
	docker build -t psr3-log-context .
	docker run --name psr3-log-context-container -d -v $(shell pwd):/app -w /app psr3-log-context

.PHONY: clean
clean:
	docker stop psr3-log-context-container
	docker rm psr3-log-context-container
	docker image rm psr3-log-context:latest

.PHONY: start
start:
	docker start psr3-log-context-container

.PHONY: stop
stop:
	docker stop psr3-log-context-container

.PHONY: test
test:
	docker exec psr3-log-context-container tools/phpunit

.PHONY: lint
lint:
	docker exec psr3-log-context-container vendor/bin/phpstan