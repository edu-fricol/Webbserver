from datetime import datetime
from pathlib import Path
from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)
FILE = Path(__file__).parent / "gast.txt"


@app.route("/", methods=["GET", "POST"])
def index():
    if request.method == "POST":
        name = request.form.get("name", "").strip().replace("|", "")
        comment = request.form.get("comment", "").strip().replace("|", "")

        if name and comment:
            comment = comment.replace("\r\n", "\n").replace("\r", "\n").replace("\n", "\\n")
            time = datetime.now().strftime("%Y-%m-%d %H:%M")

            with open(FILE, "a", encoding="utf-8") as f:
                f.write(f"{time}|{name}|{comment}\n")

        return redirect(url_for("index"))

    entries = []
    if FILE.exists():
        with open(FILE, encoding="utf-8") as f:
            for line in f:
                parts = line.rstrip("\n").split("|", 2)
                if len(parts) == 3:
                    entries.append({
                        "time": parts[0],
                        "name": parts[1],
                        "comment": parts[2].replace("\\n", "\n"),
                    })

    entries.reverse()
    return render_template("index.html", entries=entries)


if __name__ == "__main__":
    app.run(debug=True)