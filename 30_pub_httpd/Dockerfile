FROM centos:7

MAINTAINER m.ohnuma

RUN yum install -y httpd php php-mysql php-mbstring php-xml php-gd

ADD httpd.conf /etc/httpd/conf/httpd.conf
ADD php.ini /etc/php.ini

#COPY startup.sh /startup.sh
#RUN chmod a+x /startup.sh

RUN mkdir -p /var/invoice /var/report

CMD ["/usr/sbin/httpd", "-DFOREGROUND"]

#CMD ["/startup.sh"]
