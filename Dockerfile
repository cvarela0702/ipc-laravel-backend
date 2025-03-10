FROM ubuntu:latest
LABEL authors="gabriel"

ENTRYPOINT ["top", "-b"]
