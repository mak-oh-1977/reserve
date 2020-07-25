# -*- coding: utf-8 -*-
import urllib.request, json
import requests as rq
import sys
import mysql.connector
import os

TEST_HOST_S = "http://localhost/api/"


config = {}

# デーベース直接接続時は初期化必要
def Init():
    global config

    config = {
    'user': os.environ['DB_USER'],
    'password': os.environ['DB_PASS'],
    'host': os.environ['DB_HOST'],
    'port': 3306,
    'database': 'analysis'
    }

    print(config)

    os.chdir(os.path.dirname(__file__))


def dbConnect():
    global config

    con = mysql.connector.connect(**config)

    return con


def ApiReq(s, data, result='OK'):
    global config

    json_data = json.dumps(data).encode("utf-8")
    print(json_data)

    # httpリクエストを準備してPOST
    st = s.post(TEST_HOST_S, data=json_data, headers={'Content-Type':'application/json'}, timeout=600)
    print(st.text)
    if st.text.strip() == "":
        print("no server response")
        sys.exit()

    data = json.loads(st.text)
    print(json.dumps(data, indent=2, ensure_ascii=False))


    if result != '':
      if data['res'] != result:
          print("test fail")
          sys.exit(-1)

    return data

def ApiReqP(s, data, result='OK'):
    global config

    json_data = json.dumps(data).encode("utf-8")
    print(json_data)

    # httpリクエストを準備してPOST
    st = s.post(TEST_HOST_P, data=json_data, headers={'Content-Type':'application/json'}, timeout=600)
    print(st.text)
    if st.text.strip() == "":
        print("no server response")
        sys.exit(-1)

    data = json.loads(st.text)
    print(json.dumps(data, indent=2, ensure_ascii=False))


    if data['res'] != result:
        print("test fail")
        sys.exit(-1)

    return data

def ApiReqD(s, data, result='OK'):
    global config

    json_data = json.dumps(data).encode("utf-8")
    print(json_data)

    # httpリクエストを準備してPOST
    st = s.post(TEST_HOST_D, data=json_data, headers={'Content-Type':'application/json'}, timeout=600)
    print(st.text)
    if st.text.strip() == "":
        print("no server response")
        sys.exit(-1)

    data = json.loads(st.text)
    print(json.dumps(data, indent=2, ensure_ascii=False))


    if data['res'] != result:
        print("test fail")
        sys.exit(-1)

    return data

def ApiReqWithFiles(s, data, files, result='OK'):
    global config

    # httpリクエストを準備してPOST
    st = s.post(TEST_HOST_S, data=data, files=files)
    if st.text == "":
        print("no server response")
        sys.exit(-1)

    data = json.loads(st.text)
    print(json.dumps(data, indent=2, ensure_ascii=False))


    if data['res'] != result:
        print("test fail")
        sys.exit(-1)

    return data


def ApiReqPdf(s, data, result='OK'):
    global config

    json_data = json.dumps(data).encode("utf-8")
    print(json_data)

    # httpリクエストを準備してPOST
    st = s.post(TEST_HOST_PDF, data=json_data, headers={'Content-Type':'application/json'})
    print(st.text)
    if st.text.strip() == "":
        print("no server response")
        sys.exit()

    data = json.loads(st.text)
    print(json.dumps(data, indent=2, ensure_ascii=False))


    if data['res'] != result:
        print("test fail")
        sys.exit(-1)

    return data


def Test(value, result):
    if value != result:
        print("test fail")
        sys.exit(-1)

    print("test ok")

