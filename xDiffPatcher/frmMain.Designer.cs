namespace xDiffPatcher
{
    partial class frmMain
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.btnLoad = new System.Windows.Forms.Button();
            this.btnOpenDiff = new System.Windows.Forms.Button();
            this.txtDiffFile = new System.Windows.Forms.TextBox();
            this.btnOpenExe = new System.Windows.Forms.Button();
            this.label2 = new System.Windows.Forms.Label();
            this.txtExeFile = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.button1 = new System.Windows.Forms.Button();
            this.btnApplyLast = new System.Windows.Forms.Button();
            this.btnToRight = new System.Windows.Forms.Button();
            this.btnToLeft = new System.Windows.Forms.Button();
            this.btnSave = new System.Windows.Forms.Button();
            this.splitContainer1 = new System.Windows.Forms.SplitContainer();
            this.lstPatches1 = new System.Windows.Forms.ListBox();
            this.lstPatches2 = new System.Windows.Forms.ListBox();
            this.mnuStrip = new System.Windows.Forms.MenuStrip();
            this.mnuProfiles = new System.Windows.Forms.ToolStripMenuItem();
            this.groupBox1.SuspendLayout();
            this.groupBox2.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).BeginInit();
            this.splitContainer1.Panel1.SuspendLayout();
            this.splitContainer1.Panel2.SuspendLayout();
            this.splitContainer1.SuspendLayout();
            this.mnuStrip.SuspendLayout();
            this.SuspendLayout();
            // 
            // groupBox1
            // 
            this.groupBox1.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.groupBox1.Controls.Add(this.btnLoad);
            this.groupBox1.Controls.Add(this.btnOpenDiff);
            this.groupBox1.Controls.Add(this.txtDiffFile);
            this.groupBox1.Controls.Add(this.btnOpenExe);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.txtExeFile);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Location = new System.Drawing.Point(12, 32);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(540, 109);
            this.groupBox1.TabIndex = 0;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Input files";
            // 
            // btnLoad
            // 
            this.btnLoad.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnLoad.Location = new System.Drawing.Point(435, 76);
            this.btnLoad.Name = "btnLoad";
            this.btnLoad.Size = new System.Drawing.Size(99, 23);
            this.btnLoad.TabIndex = 6;
            this.btnLoad.Text = "Load!";
            this.btnLoad.UseVisualStyleBackColor = true;
            this.btnLoad.Click += new System.EventHandler(this.btnLoad_Click);
            // 
            // btnOpenDiff
            // 
            this.btnOpenDiff.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnOpenDiff.Location = new System.Drawing.Point(499, 47);
            this.btnOpenDiff.Name = "btnOpenDiff";
            this.btnOpenDiff.Size = new System.Drawing.Size(35, 23);
            this.btnOpenDiff.TabIndex = 5;
            this.btnOpenDiff.Text = "...";
            this.btnOpenDiff.UseVisualStyleBackColor = true;
            this.btnOpenDiff.Click += new System.EventHandler(this.btnOpenDiff_Click);
            // 
            // txtDiffFile
            // 
            this.txtDiffFile.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtDiffFile.Location = new System.Drawing.Point(68, 49);
            this.txtDiffFile.Name = "txtDiffFile";
            this.txtDiffFile.Size = new System.Drawing.Size(425, 20);
            this.txtDiffFile.TabIndex = 4;
            // 
            // btnOpenExe
            // 
            this.btnOpenExe.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnOpenExe.Location = new System.Drawing.Point(499, 19);
            this.btnOpenExe.Name = "btnOpenExe";
            this.btnOpenExe.Size = new System.Drawing.Size(35, 23);
            this.btnOpenExe.TabIndex = 3;
            this.btnOpenExe.Text = "...";
            this.btnOpenExe.UseVisualStyleBackColor = true;
            this.btnOpenExe.Click += new System.EventHandler(this.btnOpenExe_Click);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(6, 52);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(56, 13);
            this.label2.TabIndex = 2;
            this.label2.Text = "(x)Diff File:";
            // 
            // txtExeFile
            // 
            this.txtExeFile.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtExeFile.Location = new System.Drawing.Point(68, 21);
            this.txtExeFile.Name = "txtExeFile";
            this.txtExeFile.Size = new System.Drawing.Size(425, 20);
            this.txtExeFile.TabIndex = 1;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(6, 24);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(50, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "EXE File:";
            // 
            // groupBox2
            // 
            this.groupBox2.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.groupBox2.Controls.Add(this.button1);
            this.groupBox2.Controls.Add(this.btnApplyLast);
            this.groupBox2.Controls.Add(this.btnToRight);
            this.groupBox2.Controls.Add(this.btnToLeft);
            this.groupBox2.Controls.Add(this.btnSave);
            this.groupBox2.Controls.Add(this.splitContainer1);
            this.groupBox2.Location = new System.Drawing.Point(12, 147);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(540, 738);
            this.groupBox2.TabIndex = 1;
            this.groupBox2.TabStop = false;
            this.groupBox2.Text = "Diffydiff";
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(192, 705);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(156, 23);
            this.button1.TabIndex = 5;
            this.button1.Text = "Apply a profile...";
            this.button1.UseVisualStyleBackColor = true;
            // 
            // btnApplyLast
            // 
            this.btnApplyLast.Location = new System.Drawing.Point(192, 676);
            this.btnApplyLast.Name = "btnApplyLast";
            this.btnApplyLast.Size = new System.Drawing.Size(156, 23);
            this.btnApplyLast.TabIndex = 4;
            this.btnApplyLast.Text = "Apply last diffs";
            this.btnApplyLast.UseVisualStyleBackColor = true;
            this.btnApplyLast.Click += new System.EventHandler(this.btnApplyLast_Click);
            // 
            // btnToRight
            // 
            this.btnToRight.Location = new System.Drawing.Point(273, 647);
            this.btnToRight.Name = "btnToRight";
            this.btnToRight.Size = new System.Drawing.Size(75, 23);
            this.btnToRight.TabIndex = 3;
            this.btnToRight.Text = ">>>";
            this.btnToRight.UseVisualStyleBackColor = true;
            this.btnToRight.Click += new System.EventHandler(this.btnToRight_Click);
            // 
            // btnToLeft
            // 
            this.btnToLeft.Location = new System.Drawing.Point(192, 647);
            this.btnToLeft.Name = "btnToLeft";
            this.btnToLeft.Size = new System.Drawing.Size(75, 23);
            this.btnToLeft.TabIndex = 2;
            this.btnToLeft.Text = "<<<";
            this.btnToLeft.UseVisualStyleBackColor = true;
            this.btnToLeft.Click += new System.EventHandler(this.btnToLeft_Click);
            // 
            // btnSave
            // 
            this.btnSave.Location = new System.Drawing.Point(447, 705);
            this.btnSave.Name = "btnSave";
            this.btnSave.Size = new System.Drawing.Size(87, 23);
            this.btnSave.TabIndex = 1;
            this.btnSave.Text = "Diff\'n\'Save!";
            this.btnSave.UseVisualStyleBackColor = true;
            this.btnSave.Click += new System.EventHandler(this.btnSave_Click);
            // 
            // splitContainer1
            // 
            this.splitContainer1.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.splitContainer1.IsSplitterFixed = true;
            this.splitContainer1.Location = new System.Drawing.Point(9, 20);
            this.splitContainer1.Name = "splitContainer1";
            // 
            // splitContainer1.Panel1
            // 
            this.splitContainer1.Panel1.Controls.Add(this.lstPatches1);
            // 
            // splitContainer1.Panel2
            // 
            this.splitContainer1.Panel2.Controls.Add(this.lstPatches2);
            this.splitContainer1.Size = new System.Drawing.Size(525, 621);
            this.splitContainer1.SplitterDistance = 237;
            this.splitContainer1.SplitterWidth = 50;
            this.splitContainer1.TabIndex = 0;
            // 
            // lstPatches1
            // 
            this.lstPatches1.AllowDrop = true;
            this.lstPatches1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.lstPatches1.FormattingEnabled = true;
            this.lstPatches1.HorizontalScrollbar = true;
            this.lstPatches1.Location = new System.Drawing.Point(0, 0);
            this.lstPatches1.Name = "lstPatches1";
            this.lstPatches1.SelectionMode = System.Windows.Forms.SelectionMode.MultiExtended;
            this.lstPatches1.Size = new System.Drawing.Size(237, 621);
            this.lstPatches1.TabIndex = 0;
            // 
            // lstPatches2
            // 
            this.lstPatches2.AllowDrop = true;
            this.lstPatches2.Dock = System.Windows.Forms.DockStyle.Fill;
            this.lstPatches2.FormattingEnabled = true;
            this.lstPatches2.HorizontalScrollbar = true;
            this.lstPatches2.Location = new System.Drawing.Point(0, 0);
            this.lstPatches2.Name = "lstPatches2";
            this.lstPatches2.SelectionMode = System.Windows.Forms.SelectionMode.MultiExtended;
            this.lstPatches2.Size = new System.Drawing.Size(238, 621);
            this.lstPatches2.TabIndex = 0;
            // 
            // mnuStrip
            // 
            this.mnuStrip.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.mnuProfiles});
            this.mnuStrip.LayoutStyle = System.Windows.Forms.ToolStripLayoutStyle.Flow;
            this.mnuStrip.Location = new System.Drawing.Point(0, 0);
            this.mnuStrip.Name = "mnuStrip";
            this.mnuStrip.Size = new System.Drawing.Size(564, 23);
            this.mnuStrip.TabIndex = 2;
            this.mnuStrip.Text = "menuStrip1";
            // 
            // mnuProfiles
            // 
            this.mnuProfiles.Name = "mnuProfiles";
            this.mnuProfiles.Size = new System.Drawing.Size(58, 20);
            this.mnuProfiles.Text = "Profiles";
            this.mnuProfiles.Click += new System.EventHandler(this.mnuProfiles_Click);
            // 
            // frmMain
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(564, 925);
            this.Controls.Add(this.groupBox2);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.mnuStrip);
            this.MainMenuStrip = this.mnuStrip;
            this.Name = "frmMain";
            this.Text = "xDiffPatcher by LightFighter - DiffTeam!";
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.groupBox2.ResumeLayout(false);
            this.splitContainer1.Panel1.ResumeLayout(false);
            this.splitContainer1.Panel2.ResumeLayout(false);
            ((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).EndInit();
            this.splitContainer1.ResumeLayout(false);
            this.mnuStrip.ResumeLayout(false);
            this.mnuStrip.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.Button btnLoad;
        private System.Windows.Forms.Button btnOpenDiff;
        private System.Windows.Forms.TextBox txtDiffFile;
        private System.Windows.Forms.Button btnOpenExe;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox txtExeFile;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.GroupBox groupBox2;
        private System.Windows.Forms.SplitContainer splitContainer1;
        private System.Windows.Forms.ListBox lstPatches1;
        private System.Windows.Forms.ListBox lstPatches2;
        private System.Windows.Forms.Button btnSave;
        private System.Windows.Forms.Button btnToRight;
        private System.Windows.Forms.Button btnToLeft;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.Button btnApplyLast;
        private System.Windows.Forms.MenuStrip mnuStrip;
        private System.Windows.Forms.ToolStripMenuItem mnuProfiles;
    }
}

