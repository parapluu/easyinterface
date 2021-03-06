#! /bin/bash

. envisage/envisage_settings.sh

$ABSTOOLSHOME/frontend/bin/bash/dsaTester $@ 2> /tmp/dsa.stderr

if [ $? == 1 ]; then
    echo "<eiout>"
    echo "<eicommands>"
    echo "<printonconsole consoleid='Error'>"
    echo "<content format='text'>"
    cat /tmp/dsa.stderr
    echo "</content>"
    echo "</printonconsole>"
    echo "<dialogbox boxtitle='Execution Error' boxwidth='400'>"
    echo "<content format='html'>"
    echo "<span style='color:red;' >Execution Error</span>"
    echo "</content>"
    echo "</dialogbox>"
    echo "</eicommands>"
    echo "</eiout>"
fi

rm -f /tmp/dsa.stderr
