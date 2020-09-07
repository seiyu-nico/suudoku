FROM python:3.7
LABEL maintainer "seiyu-nico <yuseiishii141+docker@gmail.com>"
# 環境変数 PYTHONUNBUFFEREDを設定
ENV PYTHONUNBUFFERED 1
RUN  apt-get update \
  && apt-get install -y ffmpeg xterm locales \
  && apt clean \
  && rm -rf /var/lib/apt/lists/* \
  && echo "ja_JP.UTF-8 UTF-8" > /etc/locale.gen \
  && locale-gen ja_JP.UTF-8 
ENV LANG ja_JP.UTF-8 
ENV LANGUAGE ja_JP:jp \
    LC_CTYPE ja_JP.UTF-8 
RUN localedef -f UTF-8 -i ja_JP ja_JP.utf8

ENV LC_ALL ja_JP.UTF-8
RUN mkdir /code
# mkdirコマンドの実行
# 作業ディレクトリの設定
WORKDIR /code
# requirements.txtを/codeに追加する
COPY requirements.txt ./
# Pythonのパッケージ管理ツールのpipをアップグレード
# pipでrequirements.txtに指定されているパッケージを追加する
RUN pip install --upgrade pip \
    && pip install -r requirements.txt
