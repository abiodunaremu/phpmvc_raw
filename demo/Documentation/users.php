
        <br/>
        <hr/>
        <br/>

        <h2> User Services </h2>
        These are APIs used perform operations that has to do with system user
        <br/><br/>
        <h3>Register new user API</h3>
        <div class="row">
            <div class="col-2"><strong>URL</strong></div>        
            <div class="col-10">
            <a href='http://yourbooks.milliscript.com/api/users'>yourbooks.milliscript.com/api/users </a>
            <!-- <a href='http://localhost:81//yourbooks/api/user'>yourbooks.milliscript.com/api/user </a> -->
            </div>        
        </div>
        <div class="row">
            <div class="col-2"><strong>Method</strong></div>        
            <div class="col-10">POST</div>        
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">JSON PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1</td><td>firstname</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">2</td><td>lastname</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">3</td><td>phonenumber</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">4</td><td>email</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">5</td><td>gender</td><td>single character (system_options)</td>
            </tr>
            <tr>
                <th scope="row">6</td><td>country</td><td>string (system_options)</td>
            </tr>
            <tr>
                <th scope="row">7</td><td>usertype</td><td>string (system_options)</td>
            </tr>
            <tr>
                <th scope="row">8</td><td>dateofbirth</td><td>date format (yyyy-mm-dd)</td>
            </tr>
            <tr>
                <th scope="row">9</th>
                <td>requesttype</td>
                <td>post</td>
            </tr>
        </table>

        <h3>Retrieve User Information</h3>
        <div class="row">
            <div class="col-2"><strong>URL</strong></div>        
            <div class="col-10">
            <a href='http://yourbooks.milliscript.com/api/users/{userid}'>yourbooks.milliscript.com/api/users/{userid} </a>
            <!-- <a href='http://localhost:81//yourbooks/api/user'>yourbooks.milliscript.com/api/user </a> -->
            </div>        
        </div>
        <div class="row">
            <div class="col-2"><strong>Method</strong></div>        
            <div class="col-10">POST</div>        
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">JSON PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1.</th>
                <td>requesttype</td>
                <td>get</td>
            </tr>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">URL PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1</td><td>userid</td><td>string (retrieve from user session)</td>
            </tr>
        </table>

        <h3>Reset User Password</h3>
        <div class="row">
            <div class="col-2"><strong>URL</strong></div>        
            <div class="col-10">
            <a href='http://yourbooks.milliscript.com/api/users'>yourbooks.milliscript.com/api/users </a>
            <!-- <a href='http://localhost:81//yourbooks/api/user'>yourbooks.milliscript.com/api/user </a> -->
            </div>        
        </div>
        <div class="row">
            <div class="col-2"><strong>Method</strong></div>        
            <div class="col-10">POST</div>        
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">JSON PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1</td><td>email</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">2</td><td>phonenumber</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>requesttype</td>
                <td>put</td>
            </tr>
        </table>


        <h3>Search user</h3>
        <div class="row">
            <div class="col-2"><strong>URL</strong></div>        
            <div class="col-10">
            <a href='http://yourbooks.milliscript.com/api/users/*/search/{criteria}'>yourbooks.milliscript.com/api/users/*/search/{criteria} </a>
            <!-- <a href='http://localhost:81//yourbooks/api/user/*/search/{criteria}'>yourbooks.milliscript.com/api/user </a> -->
            </div>        
        </div>
        <div class="row">
            <div class="col-2"><strong>Method</strong></div>        
            <div class="col-10">POST</div>        
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">JSON PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1</td><td>sessionid</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">2</td><td>criteria</td><td>string (value for user's firstname, lastname or phone number)</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>requesttype</td>
                <td>put</td>
            </tr>
        </table>



        <h3>Upload profile picture</h3>
        <div class="row">
            <div class="col-2"><strong>URL</strong></div>        
            <div class="col-10">
            <a href='http://yourbooks.milliscript.com/api/users/{userid}/profilepicture'>yourbooks.milliscript.com/api/users/{userid}/profilepicture </a>
            <!-- <a href='http://localhost:81//yourbooks/api/user/{userid}/profilepicture'>yourbooks.milliscript.com/api/user </a> -->
            </div>        
        </div>
        <div class="row">
            <div class="col-2"><strong>Method</strong></div>        
            <div class="col-10">POST</div>        
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">JSON PARAMETERS</th>
                </tr>
                <tr>
                    <th scope="col">index</th>
                    <th scope="col">Parameter</th>
                    <th scope="col">Value</th>
                </tr>
            </thead>
            <tr>
                <th scope="row">1</td><td>sessionid</td><td>string</td>
            </tr>
            <tr>
                <th scope="row">2</td><td>userprofilepicture</td><td>file</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>requesttype</td>
                <td>put</td>
            </tr>
        </table>

