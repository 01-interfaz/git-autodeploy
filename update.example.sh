#!/bin/bash

_kill_node_process()
{
        pid=$(lsof -t -i:3000)
        kill -9 $pid
        echo "KILL NODE PROCESS PID("$pid")"
}

_git_pull()
{
        git pull
}

_node_install(){
        npm install
}

_build()
{
        npm run nuxtbuild
}

_run()
{
        node app.js
}


_execute()
{
        echo 'KILL_NODE'
        _kill_node_process
        echo 'GIT_PULL'
        _git_pull
        echo 'NODE_INSTALL'
        _node_install
        echo 'NUXT_BUILD'
        _build
        echo 'SERVER_START'
        _run
}

_execute &

