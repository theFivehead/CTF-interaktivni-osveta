from flask import Flask, request, render_template_string
import whois

app = Flask(__name__)

# Custom messages for specific domains
local_domain_list= ("kerentechai.com","obcanportalgov.cz","test.ua")

HTML_TEMPLATE = """
<!doctype html>
<title>WHOIS Lookup</title>
<h2>WHOIS Lookup Tool</h2>
<form method="post">
    <input type="text" name="domain" placeholder="Enter domain" required>
    <input type="submit" value="Lookup">
</form>

{% if result %}
    <h3>Výsledky pro {{ domain }}</h3>
    <pre>{{ result }}</pre>
{% endif %}
"""

@app.route("/", methods=["GET", "POST"])
def index():
    result = None
    custom_message = None
    domain = None

    if request.method == "POST":
        domain = request.form["domain"].strip().lower()
        if domain in local_domain_list:
            for local_domain in local_domain_list:
                if local_domain == domain:
                    with open("./whois_data/"+local_domain+".txt","r") as f:
                        result=f.read()
        else:
            try:
                w = whois.whois(domain)
                result = str(w)
            except Exception as e:
                result = f"Error: {e}"

    return render_template_string(
        HTML_TEMPLATE,
        result=result,
        domain=domain,
        custom_message=custom_message
    )

if __name__ == "__main__":
    app.run(debug=False, host="0.0.0.0", port=5000)