# -*- coding: utf-8 -*-

from flask import Flask
from redis import Redis, RedisError
import os
import socket

app = Flask(__name__)

## Minikube Multi-container
redis=Redis(host="0.0.0.0", db=0, socket_connect_timeout=2, socket_timeout=2)

@app.route("/")
def hello():
    try:
      visites = redis.incr("compteur")
    except RedisError:
      visites = "<i>Erreur de connexion Redis, compteur désactivé</i>"

    html = \
      "<h3>Hello {nom}</h3>" \
      "<b>Hostname : </b> {hostname}<br/>" \
      "<b>Visites : </b> {visites}<br/>"
      
    return html.format(nom=os.getenv("NAME", "????"), hostname=socket.gethostname(), visites=visites)


if __name__ == "__main__":
    app.debug = True
    app.run(host="0.0.0.0", port=5000)
