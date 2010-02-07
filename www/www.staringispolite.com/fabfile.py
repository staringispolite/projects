from fabric.api import * 

env.hosts = ['www.indirectmessage.com']

def host_type():
    run('uname -s')
