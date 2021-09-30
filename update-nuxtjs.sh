APP_NAME="APP-NAME"

_git_pull()
{
        echo 'GIT_PULL'
        git pull
}

_node_install()
{
        echo 'NODE_INSTALL'
        npm install
}

_build()
{
        echo 'NUXT_BUILD'
        npm run build
}

_run()
{
        echo 'SERVER_START'
        forever start -a --prod --uid $APP_NAME app.js
}

_stop()
{
        echo 'KILL_NODE'
        forever stop $APP_NAME
        #KILL PROCESS OPTIONAL
        #pid=$(lsof -t -i:3000)
        #kill -9 $pid
        #echo "KILL NODE PROCESS PID("$pid")"
}

_reset()
{
        _stop
        _run
}

_execute()
{
        _git_pull
        _node_install
        _build
        _stop        
        _run
}

_execute
