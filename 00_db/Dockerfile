FROM mysql:5.6


# COPY ./my.cnf /etc/mysql/conf.d/my.cnf

# RUN chmod 644 /etc/mysql/conf.d/my.cnf

# RUN mkdir -p /var/log/mysql
# RUN chmod 777 /var/log/mysql

# RUN apt install snapd
# RUN snap install sftpclient

CMD ["mysqld", "--innodb-use-native-aio=0"]
