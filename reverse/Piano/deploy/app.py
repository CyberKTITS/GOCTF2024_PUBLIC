from flask import Flask, request
app = Flask(__name__)

@app.route('/', methods=['POST'])
def get_flag():
  data = request.data
  if(data == b'4|1|4|1|4|3|3|3|1|3|1|3|4|4|4|1|4|1|4|3|3|3|1|3|1|3|4'):
    return r'GOCTF{C0wb0y_s0und}', 200
  return 'not found', 400

if __name__ == '__main__':        
  app.run(debug=True, host="0.0.0.0", port=5001)                        