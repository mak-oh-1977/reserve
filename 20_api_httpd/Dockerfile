FROM centos:7

MAINTAINER m.ohnuma

RUN yum -y update
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN yum remove -y php-*
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

#RUN yum install -y --enablerepo=epel php
RUN yum install -y --enablerepo=remi-php73,epel,remi,remi-safe httpd php php-pecl-redis php-pecl-zip php-imap apr-util system-logos libedit mailcap

RUN yum install -y --enablerepo=remi-php73,epel,remi,remi-safe php-mysql php-mbstring php-xml php-gd php73-php-opcache php-opcache zip unzip


ADD httpd.conf /etc/httpd/conf/httpd.conf

ADD php.ini /etc/php.ini
ADD php.conf /etc/httpd/conf.d/php.conf

COPY startup.sh /startup.sh
RUN chmod a+x /startup.sh


CMD ["/startup.sh"]

