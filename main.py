from flask import Flask, render_template, make_response, jsonify
import requests
import json
from bs4 import BeautifulSoup
from flask_cors import CORS, cross_origin
import pandas as pd
from pytz import exceptions

app = Flask("APS")
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'


def get_httpPichau(busca):
    busca = busca.replace(' ', '+')
    url = 'https://www.pichau.com.br/'
    url = '{0}catalogsearch/result/?q={1}'.format(url, busca)
    try:
        return requests.get(url)
    except (requests.exceptions.HTTPError, requests.exceptions.RequestException,
            requests.exceptions.ConnectionError, requests.exceptions.Timeout) as e:
        print(str(e))
        pass
    except Exception as e:
        raise

def get_httpKabum(busca):
    print(busca)
    busca = busca.replace(' ', '+')
    url = 'https://www.kabum.com.br/'
    url = '{0}cgi-local/site/listagem/listagem.cgi?string={1}'.format(url,busca)
    print(url)
    try:
        return requests.get(url)
    except (requests.exceptions.HTTPError, requests.exceptions.RequestException,
            requests.exceptions.ConnectionError, requests.exceptions.Timeout) as e:
        print(str(e))
        pass
    except Exception as e:
        raise

@app.route("/<busca>")
@cross_origin()
def ola(busca):
    print(busca)
    nome = busca
    r = get_httpPichau(nome)
    soup = BeautifulSoup(r.content, 'html.parser')


    print(r.url)

    meu_dicionario = {}

    for i in range(len(soup.find_all("li", class_="item product product-item"))):
        li = soup.find_all("li", class_="item product product-item")[i]
        img = soup.find_all("a", class_="product photo product-item-photo")[i]
        a = soup.find_all("a", class_="product-item-link")[i]
        span = li.find_all("span", class_="price-boleto")

        valor = ""
        try:
            valor = str(span).split(" ")[3]
        except:
            valor = str("PRODUTO INDISPONÍVEL")

        meu_dicionario[i] = {"Descricao": a.getText(),
                          "LinkProduto": str(img).split("href=")[1].split(" ")[0].split("\"")[1],
                          "LinkFoto": str(img).split("src=")[1].split(" ")[0].split("\"")[1],
                          "Valor": valor}



    return jsonify(meu_dicionario)

@app.route("/kabum/<busca>")
@cross_origin()
def olaa(busca):
    print(busca)
    nome = busca

    meu_dicionario = {}

    r = get_httpKabum(nome)
    soup = BeautifulSoup(r.content, 'html.parser')

    try:
        return str(soup).split("const listagemDados =")[1].split("const listagemErro =")[0]
    except:
        return "500"

@app.route("/magalu/<busca>")
@cross_origin()
def olaaa(busca):
    print(busca)
    nome = busca

    url = "https://www.magazineluiza.com.br/busca/"+busca.replace(" ", "%20")
    r = get_httpPichau(nome)

    url = requests.get(url)
    soup = BeautifulSoup(url.content, 'html.parser')

    h3 = soup.find_all("h3", class_="productTitle")
    img = soup.find_all("img", class_="product-image")
    span = soup.find_all("span", class_="price")
    a = soup.find_all("a", class_="product-li")

    meu_dicionario = {}

    for i in range(len(h3)):
        try:
            valor = str(span[i].getText()).replace(" ", "").split("R$")[1].split("\n")[0]
        except:
            valor = "PRODUTO INDISPONÍVEL"

        meu_dicionario[i] = {"Descricao": h3[i].getText(),
                          "LinkProduto": str(a).split("href=\"")[i + 1].split("\"")[0],
                          "LinkFoto": str(img[i]).split("data-original=\"")[1].split("\"")[0],
                          "Valor": valor}

    return jsonify(meu_dicionario)


app.run()