from __future__ import with_statement
from fabric.api import *


env.hosts = ['www@staringispolite.com']

def linux_type(): 
    '''
    Just connect to each server and print type of linux
    '''
    run('uname -s')


def prepare_deploy_www_staringispolite_com():
    '''
    Pack up www.staringispolite.com for sending (no tests yet)
    '''
    pass


def deploy_www_staringispolite_com():
    '''
    Actually perform the deploy
    '''
    code_dir = '/Users/jhoward/Code/github/projects/www/www.staringispolite.com/'
    remote_host = 'www@staringispolite.com'
    remote_dir = '/var/www/www.staringispolite.com/'
    local('rsync %sindex.html %s:%s' % (code_dir, remote_host, remote_dir))
    local('rsync -avr %sblog/ %s:%sblog' % (code_dir, remote_host, remote_dir))
    local('rsync -avr %slikepython/ %s:%slikepython' % (code_dir, remote_host, remote_dir))
    local('rsync -avr %ssevenup/ %s:%ssevenup' % (code_dir, remote_host, remote_dir))
    local('rsync -avr %ssgb/ %s:%ssgb' % (code_dir, remote_host, remote_dir))
    local('rsync -avr %snas/ %s:%ssgb' % (code_dir, remote_host, remote_dir))


def deploy():
   '''
   Deploy all properties
   '''
   deploy_www_staringispolite_com()


