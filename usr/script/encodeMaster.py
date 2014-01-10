#!/usr/bin/env python
import sqlite3
import time
import subprocess
import os
from daemon import runner

def processQueue(db):
	task = db.execute('SELECT id,format,resource FROM cache WHERE progress IS NULL AND (strftime(\'%s\',\'now\')-strftime(\'%s\',ts))<60 ORDER BY id').fetchone()
	if task:
		db.execute('UPDATE cache SET progress=0 WHERE id=?', (task['id'],));
		db.commit()
		print 'Starting task %d' % task['id']
		if task['format'] == 1:
			subprocess.call(['avconv', '-y',
								'-f', 'ogg',
								'-i', '/var/resources/%d' % task['resource'],
								'-b:a', '64K',
								'-f', 'mp3',
								'/var/cache/resources/%d' % task['id']])
		db.execute('UPDATE cache SET progress=1000 WHERE id=?', (task['id'],));
		db.commit()
		print 'Finished task %d' % task['id']
		return True
	else:
		return False

class App():
    def __init__(self):
        self.stdin_path = '/dev/null'
        self.stdout_path = '/dev/tty'
        self.stderr_path = '/dev/tty'
        self.pidfile_path =  '/var/lock/encodeMaster.pid'
        self.pidfile_timeout = 5
    def run(self):
		db = sqlite3.connect('/var/db/victory.sqlite3')
		db.row_factory = sqlite3.Row
		db.execute('PRAGMA synchronous = OFF')
		open('/var/lock/encodeMaster', 'w+').close()
		os.chmod('/var/lock/encodeMaster', 0o666)
		mtime = 0
		while True:
			newMtime = os.path.getmtime('/var/lock/encodeMaster')
			if newMtime > mtime:
				while processQueue(db):
					pass
				mtime = newMtime
			else:
				time.sleep(5)
app = App()
daemon_runner = runner.DaemonRunner(app)
daemon_runner.do_action()
