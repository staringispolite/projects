from fabric.api import *


env.hosts = ['www@174.143.158.94']

def linux_type(): 
    '''
    Just connect to each server and print type of linux
    '''
    run('uname -s')


def prepare_deploy_www_staringispolite_com():
    '''
    Pack up www.staringispolite.com for sending (no tests yet)
    '''
    local('tar -czf /tmp/www.staringispolite.com.tgz')


def deploy_www_staringispolite_com():
    '''
    Actually perform the deploy
    '''
    prepare_deploy_www_staringispolite_com()
    put('/tmp/www.staringispolite.com.tgz', '/tmp/)
    with cd('/var/www/www.staringispolite.com/')
        run('tar -xzvf /tmp/my_project.tgz')

def deploy():
   '''
   Deploy all properties
   '''
   deploy_www_staringispolite_com()


