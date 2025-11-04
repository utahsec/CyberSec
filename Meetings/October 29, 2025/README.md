# UtahSec meeting 2025-10-29

CTF technical skills workshop on basic RSA factoring attacks.

There are two challenges (in order, easiest first):

* [lookup](1-lookup)
* [shared](2-shared)

## Setup

Ensure that these prerequisites are installed on your system:

* Code editor (e.g. VSCode)
* Python 3

Open the terminal, create a directory for this workshop, and navigate to it. On Windows, you will likely run into less problems with Command Prompt than Powershell when activating the Python venv later.

Create a Python venv called `env` for this workshop:

```
python -m venv env
```

Activate the venv.

Windows: 

```
env\Scripts\activate
```

Linux/macOS:

```
. env/bin/activate
```

Install the PyCryptodome library in the venv:

```
pip install pycryptodome
```

Your environment should now be ready to run the solution scripts!

```
cd <challenge directory>
python sol.py
```
