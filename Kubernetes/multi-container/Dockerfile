FROM python:3.10-slim
WORKDIR /app
COPY . /app
RUN pip install -r requirements.txt
## Uniquement pour docker & docker-compose
# EXPOSE 5000
# ENV NOM Gilles
CMD ["python", "app.py"]
