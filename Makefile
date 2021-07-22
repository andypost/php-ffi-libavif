.PHONY: all up build run deps down exec images check

AVIF=libavif

all: up images check

up: build run deps

build: docker/Dockerfile
	docker build -t ${AVIF} docker

run:
	docker run -d --rm -v $(PWD):/mnt -w /mnt --name ${AVIF} ${AVIF}

deps:
	docker exec ${AVIF} apk add php8 php8-ffi libavif

logs:
	docker logs --tail 100 ${AVIF}

down:
	docker stop ${AVIF}

exec:
	docker exec -it ${AVIF} ash


images:
	curl -fSL --create-dirs https://raw.githubusercontent.com/link-u/avif-sample-images/master/kimono.avif -o images/kimono.avif
	curl -fSL --create-dirs https://raw.githubusercontent.com/link-u/avif-sample-images/master/star-12bpc.avifs -o images/star-12bpc.avifs
	curl -fSL https://github.com/AOMediaCodec/libavif/raw/v0.9.0/tests/data/io/extentsalpha.avif -o images/extentsalpha.avif
	curl -fSL https://github.com/AOMediaCodec/libavif/raw/v0.9.0/tests/data/io/twoextents.avif -o images/twoextents.avif

check:
	docker exec ${AVIF} php8 testit.php
