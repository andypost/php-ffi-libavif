FROM alpine:edge

RUN set -e && apk add gcc musl-dev
WORKDIR /tmp
COPY pause/pause.c pause.c
RUN gcc -Os pause.c -o pause && strip pause

FROM alpine:edge

COPY --from=0 /tmp/pause /usr/bin/pause
CMD ["/usr/bin/pause"]
