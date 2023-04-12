/*globals scrum */

// Add a plugin to load tickets from local JIRA server
scrum.sources.push({
  // Fixed properties and methods
  name: "JIRA",
  position: 3,
  view: "templates/jira_source.html",
  feedback: false,
  jql: "",
  disable_jira_fields: false,
  // Feedback call for completed poll
  completed: function (result) {},

  // Custom properties and methods
  loaded: false,
  issues: [],
  issue: {},
  event: ["poll", "start", "JIRA"],

  load: function () {
    var self = this;

    var queryParameters = $.param({
      base_url: this.base_url,
      username: this.username,
      password: this.password,
      project: this.project,
      jql: this.jql,
    });

    this.parent
      .$http({
        url: "/api/jira/getIssues",
        method: "POST",
        data: queryParameters,
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
      })
      .then(function (response) {
        var data = response.data;

        if (!data || !data.issues) {
          self.error = "Can't load Jira issues, check configuration";
        } else {
          var converter = new showdown.Converter();

          //sort the data.issues as in Jira view, from lowest RD priority to highest and from the newly created to oldest
          response.data.issues.sort(function (a, b) {
            if (a.fields.customfield_38721 === b.fields.customfield_38721) {
              return new Date(b.fields.created) - new Date(a.fields.created);
            }
            return a.fields.customfield_38721 - b.fields.customfield_38721;
          });

          // Convert JIRA format to Markdown and then to HTML
          response.data.issues.forEach(function (issue) {
            var markdown = J2M.toM(issue.fields.description || "");
            issue.fields.description = converter.makeHtml(markdown);
          });
          self.issues = response.data.issues;
          self.issue = self.issues[0];
          self.base_url = response.data.base_url;
          self.loaded = true;
        }
      });
  },
  reload: function () {
    this.loaded = false;
  },
});
