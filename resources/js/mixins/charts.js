export default{
    methods:{
        createChartDataset() {
            let datasets = [...this.additionalDatasets];

            for (let data in this.dataset) {
                datasets.unshift(
                    {
                        ...this.dataset[data],
                        ...{fill:false}
                    }
                );
            }

            return {
                labels: this.settings.parameters,
                datasets: datasets
            }
        },

        replaceToolTipTemplate() {
            if (this.settings.toolTipTemplate && this.settings.toolTipTemplate.body) {
                const toolTipBodyTemplate = this.settings.toolTipTemplate.body;
                const toolTipTitleTemplate = this.settings.toolTipTemplate.title;
                this.options.tooltips = {
                    // Disable the on-canvas tooltip
                    enabled: false,

                    custom: function (tooltipModel) {
                        // Tooltip Element
                        var tooltipEl = document.getElementById('chartjs-tooltip');

                        // Create element on first render
                        if (!tooltipEl) {
                            tooltipEl = document.createElement('div');
                            tooltipEl.id = 'chartjs-tooltip';
                            tooltipEl.innerHTML = '<table></table>';
                            document.body.appendChild(tooltipEl);
                        }

                        // Hide if no tooltip
                        if (tooltipModel.opacity === 0) {
                            tooltipEl.style.opacity = 0;
                            return;
                        }

                        // Set caret Position
                        tooltipEl.classList.remove('above', 'below', 'no-transform');
                        if (tooltipModel.yAlign) {
                            tooltipEl.classList.add(tooltipModel.yAlign);
                        } else {
                            tooltipEl.classList.add('no-transform');
                        }

                        function getBody(bodyItem) {
                            return bodyItem.lines;
                        }

                        // Set Text
                        if (tooltipModel.body) {
                            var titleLines = tooltipModel.title || [];
                            var bodyLines = tooltipModel.body.map(getBody);

                            var innerHtml = '<thead>';

                            titleLines.forEach(function (title) {
                                var newTitle = title;
                                if (toolTipTitleTemplate) {
                                    newTitle = toolTipTitleTemplate.replace('@TITLE@', title);
                                }
                                innerHtml += '<tr><th>' + newTitle + '</th></tr>';
                            });
                            innerHtml += '</thead><tbody>';

                            bodyLines.forEach(function (body, i) {
                                var colors = tooltipModel.labelColors[i];
                                var bodyParts = body.toString().split(':', 2);

                                var toolTipContent = toolTipBodyTemplate;
                                toolTipContent = toolTipContent.replace('@BACK_COLOR@', colors.backgroundColor);
                                toolTipContent = toolTipContent.replace('@BORDER_COLOR@', colors.borderColor);
                                toolTipContent = toolTipContent.replace('@LABEL@', bodyParts[0]);
                                toolTipContent = toolTipContent.replace('@VALUE@', bodyParts[1] || '');
                                toolTipContent = toolTipContent.replace(/@TOOL_TIP_MODEL.(\w+)@/g, function (pattern, variable) {
                                    return tooltipModel[variable] || pattern;
                                })

                                innerHtml += '<tr><td>' + toolTipContent + '</td></tr>';
                            });
                            innerHtml += '</tbody>';

                            var tableRoot = tooltipEl.querySelector('table');
                            tableRoot.innerHTML = innerHtml;
                        }

                        // `this` will be the overall tooltip
                        var position = this._chart.canvas.getBoundingClientRect();

                        // Display, position, and set styles for font
                        tooltipEl.style.opacity = 1;
                        tooltipEl.style.position = 'absolute';
                        tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                        tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                        tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                        tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                        tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                        tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
                        tooltipEl.style.pointerEvents = 'none';
                    }
                };
            }
        }
    }
}
