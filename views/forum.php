

<body>

<div id="container">

    <!-- LISTE DES CATÉGORIES -->
    <div class="category-list">
        <h2>Catégories</h2>
        <div class="category" onclick="openCategory('films')">🎥 Films</div>
        <div class="category" onclick="openCategory('realisateurs')">🎞️ Réalisateurs</div>
        <div class="category" onclick="openCategory('suggestions')">💡 Suggestions des habitants</div>
        <div class="category" onclick="openCategory('archives')">📚 Archives anciennes éditions</div>
    </div> 

    <!-- LISTE DES SUJETS -->
    <div class="topic-list" id="topicList" style="display:none;">
        <h2 id="catTitle"></h2>

        <button onclick="showCreateTopic()">➕ Créer un sujet</button>

        <div id="topics"></div>
    </div>

    <!-- DISCUSSION -->
    <div class="messages" id="discussion" style="display:none;">
        <h2 id="topicTitle"></h2>

        <div id="messageList"></div>

        <h3>Répondre :</h3>
        <textarea id="replyText" placeholder="Votre réponse..."></textarea>
        <button onclick="addMessage()">Envoyer</button>
    </div>

</div>


<script>
    // ---- DONNÉES STOCKÉES EN LOCAL ----
    let forumData = JSON.parse(localStorage.getItem("forumCinema") || "{}");

    function save() {
        localStorage.setItem("forumCinema", JSON.stringify(forumData));
    }

    let currentCategory = null;
    let currentTopic = null;

    // ---- OUVRIR UNE CATÉGORIE ----
    function openCategory(cat) {
        currentCategory = cat;

        document.getElementById("topicList").style.display = "block";
        document.getElementById("discussion").style.display = "none";

        const titles = {
            films: "🎥 Films",
            realisateurs: "🎞️ Réalisateurs",
            suggestions: "💡 Suggestions des habitants",
            archives: "📚 Archives"
        };

        document.getElementById("catTitle").textContent = titles[cat];

        if (!forumData[cat]) forumData[cat] = {};

        renderTopics();
    }

    // ---- AFFICHER LES SUJETS ----
    function renderTopics() {
        const topicsDiv = document.getElementById("topics");
        topicsDiv.innerHTML = "";

        const topics = forumData[currentCategory];

        for (let t in topics) {
            const div = document.createElement("div");
            div.className = "topic";
            div.textContent = t + " (" + topics[t].messages.length + " messages)";
            div.onclick = () => openTopic(t);
            topicsDiv.appendChild(div);
        }
    }

    // ---- CRÉATION DE SUJET ----
    function showCreateTopic() {
        const title = prompt("Titre du sujet :");

        if (!title) return;

        forumData[currentCategory][title] = { messages: [] };
        save();
        renderTopics();
    }

    // ---- OUVRIR UN SUJET ----
    function openTopic(title) {
        currentTopic = title;

        document.getElementById("discussion").style.display = "block";
        document.getElementById("topicTitle").textContent = title;

        renderMessages();
    }

    // ---- AFFICHER LES MESSAGES ----
    function renderMessages() {
        const msgDiv = document.getElementById("messageList");
        msgDiv.innerHTML = "";

        const messages = forumData[currentCategory][currentTopic].messages;

        messages.forEach((msg, index) => {
            const m = document.createElement("div");
            m.className = "message";
            m.innerHTML = `
            <div>${msg.text}</div>
            <span class="vote" onclick="upvote(${index})">⬆️ ${msg.votes}</span>
        `;
            msgDiv.appendChild(m);
        });
    }

    // ---- AJOUTER UN MESSAGE ----
    function addMessage() {
        const text = document.getElementById("replyText").value;

        if (!text.trim()) return;

        forumData[currentCategory][currentTopic].messages.push({
            text,
            votes: 0
        });

        document.getElementById("replyText").value = "";
        save();
        renderMessages();
    }

    // ---- SYSTEME DE VOTE ----
    function upvote(index) {
        forumData[currentCategory][currentTopic].messages[index].votes++;
        save();
        renderMessages();
    }
</script>


</body>


